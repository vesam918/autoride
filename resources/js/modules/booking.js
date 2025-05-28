/**
 * Booking Module
 * Handles all chauffeur booking related functionality
 */

import axios from 'axios';
import { showNotification } from '../app';

class BookingSystem {
    constructor() {
        this.form = document.querySelector('#booking-form');
        this.vehicleSelect = document.querySelector('#vehicle-select');
        this.dateInput = document.querySelector('#booking-date');
        this.timeInput = document.querySelector('#booking-time');
        this.pickupLocation = document.querySelector('#pickup-location');
        this.dropoffLocation = document.querySelector('#dropoff-location');
        this.priceDisplay = document.querySelector('#price-display');
        this.submitButton = document.querySelector('#booking-submit');
        
        this.state = {
            vehicle: null,
            date: null,
            time: null,
            pickup: null,
            dropoff: null,
            price: 0,
            loading: false
        };

        this.init();
    }

    init() {
        if (!this.form) return;

        // Initialize date picker
        this.initializeDatePicker();

        // Initialize location autocomplete
        this.initializeLocationAutocomplete();

        // Setup event listeners
        this.setupEventListeners();

        // Initialize vehicle selection
        this.initializeVehicleSelection();
    }

    initializeDatePicker() {
        if (!this.dateInput) return;

        // Get minimum date (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);

        // Set minimum date
        this.dateInput.min = tomorrow.toISOString().split('T')[0];

        // Set maximum date (6 months from now)
        const maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 6);
        this.dateInput.max = maxDate.toISOString().split('T')[0];
    }

    initializeLocationAutocomplete() {
        const options = {
            types: ['address'],
            componentRestrictions: { country: 'US' } // Modify based on your requirements
        };

        if (this.pickupLocation) {
            const pickupAutocomplete = new google.maps.places.Autocomplete(
                this.pickupLocation,
                options
            );
            pickupAutocomplete.addListener('place_changed', () => {
                const place = pickupAutocomplete.getPlace();
                this.state.pickup = place;
                this.calculatePrice();
            });
        }

        if (this.dropoffLocation) {
            const dropoffAutocomplete = new google.maps.places.Autocomplete(
                this.dropoffLocation,
                options
            );
            dropoffAutocomplete.addListener('place_changed', () => {
                const place = dropoffAutocomplete.getPlace();
                this.state.dropoff = place;
                this.calculatePrice();
            });
        }
    }

    setupEventListeners() {
        // Vehicle selection
        if (this.vehicleSelect) {
            this.vehicleSelect.addEventListener('change', (e) => {
                this.state.vehicle = e.target.value;
                this.calculatePrice();
            });
        }

        // Date selection
        if (this.dateInput) {
            this.dateInput.addEventListener('change', (e) => {
                this.state.date = e.target.value;
                this.checkAvailability();
            });
        }

        // Time selection
        if (this.timeInput) {
            this.timeInput.addEventListener('change', (e) => {
                this.state.time = e.target.value;
                this.checkAvailability();
            });
        }

        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleBookingSubmission();
            });
        }
    }

    async calculatePrice() {
        if (!this.state.pickup || !this.state.dropoff || !this.state.vehicle) {
            return;
        }

        try {
            const response = await axios.post(
                `${window.autorideData.ajaxUrl}?action=calculate_price`,
                {
                    pickup: this.state.pickup.formatted_address,
                    dropoff: this.state.dropoff.formatted_address,
                    vehicle: this.state.vehicle,
                    nonce: window.autorideData.nonce
                }
            );

            if (response.data.success) {
                this.state.price = response.data.price;
                if (this.priceDisplay) {
                    this.priceDisplay.textContent = `$${this.state.price}`;
                }
            }
        } catch (error) {
            console.error('Price calculation error:', error);
            showNotification('error', 'Error calculating price. Please try again.');
        }
    }

    async checkAvailability() {
        if (!this.state.date || !this.state.time || !this.state.vehicle) {
            return;
        }

        try {
            const response = await axios.post(
                `${window.autorideData.ajaxUrl}?action=check_availability`,
                {
                    date: this.state.date,
                    time: this.state.time,
                    vehicle: this.state.vehicle,
                    nonce: window.autorideData.nonce
                }
            );

            if (response.data.success) {
                if (!response.data.available) {
                    showNotification('error', 'Selected time slot is not available.');
                    this.submitButton.disabled = true;
                } else {
                    this.submitButton.disabled = false;
                }
            }
        } catch (error) {
            console.error('Availability check error:', error);
            showNotification('error', 'Error checking availability. Please try again.');
        }
    }

    async handleBookingSubmission() {
        if (this.state.loading) return;

        this.state.loading = true;
        this.submitButton.disabled = true;

        try {
            const formData = new FormData(this.form);
            const response = await axios.post(
                `${window.autorideData.ajaxUrl}?action=create_booking`,
                formData,
                {
                    headers: {
                        'X-WP-Nonce': window.autorideData.nonce
                    }
                }
            );

            if (response.data.success) {
                showNotification('success', 'Booking created successfully!');
                window.location.href = response.data.redirect;
            } else {
                throw new Error(response.data.message);
            }
        } catch (error) {
            console.error('Booking submission error:', error);
            showNotification('error', error.message || 'Error creating booking. Please try again.');
            this.submitButton.disabled = false;
        } finally {
            this.state.loading = false;
        }
    }

    initializeVehicleSelection() {
        const vehicleCards = document.querySelectorAll('.vehicle-card');
        vehicleCards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove active class from all cards
                vehicleCards.forEach(c => c.classList.remove('active'));
                
                // Add active class to selected card
                card.classList.add('active');
                
                // Update vehicle select value
                if (this.vehicleSelect) {
                    this.vehicleSelect.value = card.dataset.vehicleId;
                    this.state.vehicle = card.dataset.vehicleId;
                    this.calculatePrice();
                }
            });
        });
    }
}

// Initialize booking system
document.addEventListener('DOMContentLoaded', () => {
    new BookingSystem();
});

export default BookingSystem;

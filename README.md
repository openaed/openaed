# OpenAED

OpenAED is a map application built with Laravel that displays Automated External Defibrillators (AEDs) using data from OpenStreetMap. This application aims to provide a user-friendly interface for locating AEDs in your vicinity, which can be crucial in emergency situations.

## Features

-   Interactive map displaying AED locations
-   Uses OpenStreetMap data for accurate and up-to-date information
-   Simple and intuitive user interface

## Setup

Follow these steps to set up the project on your local machine:

```bash
# Clone the repository
git clone https://github.com/openaed/openaed.git
cd openaed

# Install the dependencies
composer install
npm install

# Build the assets
npm run dev # or npm run build for production

# Copy the .env.example file to .env and set up your environment variables
cp .env.example .env

# Generate an application key
php artisan key:generate

# Run the migrations
php artisan migrate

# Serve the application
php artisan serve # Or use your apache/nginx/whatever server
```

## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request if you have any suggestions or improvements.

## License

This project is open-source and available under the [Apache 2.0](./LICENSE) license.

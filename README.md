# CalculateDistances API

The CalculateDistances API and command allows you to calculate the distances between multiple addresses and Adchieve headquarters address. 
The results are sorted by distance to the Adchieve headquarters and saved as csv file named distances.csv in the storage directory.

## Prerequisites

Without docker-compose:

- PHP (>= 8)
- Composer
- Laravel (>= 9.19)
- Positionstack API Key (for geolocation data)
- Composer packages (Guzzle HTTP Client, League CSV)

Installation with docker-compose
- clone project
- run docker-compose build
- run docker compose up
### API Usage

- **Endpoint:** `/api/geolocation/distances`
- **Method:** POST
- **Content-Type:** application/json

## Request Format

The API expects a JSON request with an array of addresses. The first address in the array is used as the reference point, and distances to all other addresses are calculated relative to this reference point.

#### Request Example:
To use the CalculateDistances API, open your terminal. Then, run the following command:

```bash
curl -X POST -H "Content-Type: application/json" -d '{
    "addresses": [
        "Eastern Enterprise B.V. - Deldenerstraat 70, 7551AH Hengelo, The Netherlands",
        "Eastern Enterprise - 46/1 Office no 1 Ground Floor , Dada House , Inside dada silk mills compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India",
        "Adchieve Rotterdam - Weena 505, 3013 AL Rotterdam, The Netherlands",
        "Sherlock Holmes - 221B Baker St., London, United Kingdom",
        "The White House - 1600 Pennsylvania Avenue, Washington, D.C., USA",
        "The Empire State Building - 350 Fifth Avenue, New York City, NY 10118",
        "The Pope - Saint Martha House, 00120 Citta del Vaticano, Vatican City",
        "Neverland - 5225 Figueroa Mountain Road, Los Olivos, Calif. 93441, USA"
    ]
}' http://locahost:8000/api/geolocation/distances
```
#### Output Example:
```json
{
    "distances": {
        "2": {
            "name": "Sherlock Holmes",
            "address": "221B Baker St., London, United Kingdom",
            "distance": 483.41
        },
        "1": {
            "name": "Adchieve Rotterdam",
            "address": "Weena 505, 3013 AL Rotterdam, The Netherlands",
            "distance": 573.08
        },
        "5": {
            "name": "The Pope",
            "address": "Saint Martha House, 00120 Citta del Vaticano, Vatican City",
            "distance": 646.1
        },
        "4": {
            "name": "The Empire State Building",
            "address": "350 Fifth Avenue, New York City, NY 10118",
            "distance": 5986.36
        },
        "3": {
            "name": "The White House",
            "address": "1600 Pennsylvania Avenue, Washington, D.C., USA",
            "distance": 6313.59
        },
        "0": {
            "name": "Eastern Enterprise",
            "address": "46/1 Office no 1 Ground Floor , Dada House , Inside dada silk mills compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India",
            "distance": 6543.36
        },
        "6": {
            "name": "Neverland",
            "address": "5225 Figueroa Mountain Road, Los Olivos, Calif. 93441, USA",
            "distance": 9063.93
        }
    }
}
```

### Command Usage

To use the CalculateDistances command, open your terminal and navigate to the project's root directory. Then, run the following command:

```bash
php artisan calculate:distances

Enter the first address:
test - 123 Main St, City1, Country1
Enter the next address or q to finish:
test2 - 456 Elm St, City2, Country2
Enter the next address or q to finish:
q

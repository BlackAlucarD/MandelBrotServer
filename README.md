# MandelBrot Serverseite
## Server Lokal starten
### Unter Windoof
1. xampp installieren
2. composer installieren
3. in der xampp "httpd.conf" den DocumentRoot ändern auf "MandelBrotServer/public/"
4. auf der root Ebene vom MandelBrotServer ```composer install``` ausführen
### Unter Linux
Coming soon...
## Usage
POST-Anfragen an localhost oder IP-Adresse.

### Request
Die Api erwartet ein JSON header im POST Request mit folgenden Schlüsseln:
* realFrom
* realTo
* imaginaryFrom
* imaginaryTo
* intervall
* maxIteration

Beispiel JSON: 
```json
{
    "realFrom" : "-2",
    "realTo" : "2",
    "imaginaryFrom" : "-2",
    "imaginaryTo" : "2",
    "intervall" : "0.01",
    "maxIteration" : "255"
}
```

### Response
Die Api gibt ein eindimensionales JSON Objekt wieder zurück, welche die MandelBrot-Menge enthält.

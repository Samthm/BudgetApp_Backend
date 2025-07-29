# 💰 BudgetApp Backend API
**REST-API für die BudgetApp**  
*Verwaltet Benutzerkonten, Transaktionen und Budget-Analysen*

![PHP Version](https://img.shields.io/badge/PHP-8.x-purple)
![Database](https://img.shields.io/badge/MySQL-8.0-blue)
![GitHub License](https://img.shields.io/badge/License-MIT-green)

---

## 📌 Hauptfunktionen
- 🔐 Sichere Authentifizierung (JWT)
- ➕ Erstelle/Edfiniere/Lösche Transaktionen
- 📊 Monatliche Budget-Berichte
- 🔄 Nahtlose Integration mit [Frontend](https://github.com/Issaegy/BudgetApp)

---

## 🚀 Schnellstart

### Voraussetzungen
- PHP 8.0+
- Composer
- MySQL 5.7+

### Installation
1. Repository klonen:
   ```bash
   git clone https://github.com/DEIN_USERNAME/BudgetApp-Backend.git
   cd BudgetApp-Backend



 2.  Konfiguration:

bash
cp .env.example .env
Bearbeite .env mit deinen Datenbank-Zugangsdaten.

3. Abhängigkeiten installieren:

bash
composer install

4. Datenbank einrichten:

bash
php artisan migrate --seed


5. Server starten:
bash
php artisan serve
→ API läuft auf http://localhost:8000


🛠 Entwicklung
Testen
bash
php artisan test
Beitragende
Fork das Projekt

Erstelle einen Feature-Branch (git checkout -b feature/neue-funktion)

Commite deine Änderungen (git commit -m "Add neue-funktion")

Pushe den Branch (git push origin feature/neue-funktion)

Öffne einen Pull Request

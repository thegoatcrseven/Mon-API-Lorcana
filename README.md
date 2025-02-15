# MonAPI - API Laravel pour Disney Lorcana

Une API Laravel pour accéder aux données des cartes du jeu Disney Lorcana.

## Fonctionnalités

- Récupération de toutes les cartes
- Recherche d'une carte par ID
- Liste des sets disponibles
- Récupération des cartes par set
- Mise en cache des données pour de meilleures performances

## Installation

1. Cloner le dépôt :
```bash
git clone https://github.com/votre-username/monapi.git
cd monapi
```

2. Installer les dépendances :
```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Télécharger les données Lorcana :
```bash
chmod +x download_lorcana_data.sh
./download_lorcana_data.sh
```

## Utilisation

### Endpoints API

- `GET /api/cards` : Liste de toutes les cartes
- `GET /api/cards/{id}` : Détails d'une carte spécifique
- `GET /api/sets` : Liste de tous les sets
- `GET /api/sets/{id}` : Détails d'un set spécifique
- `GET /api/sets/{id}/cards` : Liste des cartes d'un set spécifique



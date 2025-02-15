#!/bin/bash

# Configuration
VERSION="2025-01-15-2.1.0-1"
STORAGE_DIR="storage/app/lorcana"
BASE_URL="https://lorcanajson.org/files/$VERSION/fr"

# Créer le répertoire de données
mkdir -p "$STORAGE_DIR"

# Fonction de téléchargement
download_file() {
    local filename=$1
    echo "Téléchargement de $filename..."
    curl -L -o "$STORAGE_DIR/$filename" "$BASE_URL/$filename"
}

# Télécharger allCards.json
download_file "allCards.json"

# Télécharger les données des sets
for i in {1..6} "Q1"; do
    download_file "setdata.$i.json"
done

echo "Téléchargement terminé !"

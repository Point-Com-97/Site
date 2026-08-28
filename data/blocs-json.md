// Structure du bloc Texte
texte : {"contenu": "Texte riche..."}

// Structure du bloc Image
image : {"media_id": 4, "legende": "..."}

// Structure du bloc Vidéo
video : {"url": "https://.../embed/xxx", "legende": "..."}

// Structure du bloc Stats via Charts.js
stats : {
    "type": "bar",
    "data": {
        "labels": [
            "Taux de reussite",
            "Taux d'insertion",
            "Taux d'abandon"
        ],
        "datasets": [
            {
                "label": "Indicateurs 2025",
                "data": [
                    95,
                    80,
                    5
                ]
            }
        ]
    }
}

// Structure du bloc Tableau
tableau : {"colonnes": ["Année", "Taux"], "lignes": [["2023", "90%"], ["2024", "95%"]]}
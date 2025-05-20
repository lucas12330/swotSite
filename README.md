# swotSite

Un projet complet pour collecter des données de capteurs, les stocker, les analyser et afficher une matrice SWOT interactive via un site web.

## Structure du projet

1. **Acquisition de données**  
   - `Arduino/` : code pour le capteur de base  
   - `ESP32_Sonar/` : mesure de distance  
   - `ESP32_POST/` : envoi HTTP des mesures  

2. **Transmission & API**  
   - `api/` : serveur PHP et définition des endpoints  

3. **Stockage**  
   - `database/` : schéma et scripts de la base de données  

4. **Traitement & Analyse**  
   - `processing/` : modules PHP de traitement des données et calcul SWOT  

5. **Interface Web**  
   - `web/` : front-end HTML/CSS/JS pour afficher la matrice SWOT  

6. **Déploiement**  
   - `deployment/` : configurations Apache/Nginx, `.env`, Makefile, etc.

flowchart LR
    subgraph Acquérir
      LiDAR[Capteur LiDAR]
      Ultra[Capteur Ultrason]
    end

    subgraph Traiter
      ESP32_Proc[ESP32]
    end

    subgraph Communiquer
      ESP32_Comm[ESP32]
    end

    LiDAR --> ESP32_Proc
    Ultra --> ESP32_Proc
    ESP32_Proc --> ESP32_Comm


## Installation rapide

1. Cloner le dépôt  
   ```bash
   git clone https://github.com/lucas12330/swotSite.git
   cd swotSite

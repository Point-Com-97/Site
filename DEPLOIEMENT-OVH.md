# Deploiement OVH Cloud Web Eco - Offre Perso

## Architecture

```
/ (racine FTP)
├── .htaccess              # Protection dossiers sensibles
├── .env                   # Configuration (NE PAS COMMITTER)
├── public/                # DocumentRoot (seul dossier accessible web)
│   ├── .htaccess          # Front controller + securite HTTP
│   ├── index.php          # Point d'entree
│   ├── admin/             # Administration
│   ├── uploads/           # Fichiers uploades (NON versionnes)
│   └── assets/
│       ├── css/           # CSS COMPILES - versionnes dans Git
│       ├── scss/          # Sources SCSS (reference, non servis au navigateur)
│       └── vendor|font|image|video/
├── src/                   # Code source PHP (NON accessible web)
├── data/
│   ├── config/            # database.php + mail.php VERSIONNES (sans secret, lisent .env)
│   └── sql/               # Schema + seed (NON accessible web)
├── admin/                 # Auth admin (NON accessible web)
├── var/tmp/               # Logs (NON accessible web - a creer en FTP)
└── vendor/                # Dependances Composer
```

## Important : pas de build sur l'offre Perso

L'offre Perso n'a **ni SSH, ni Composer, ni Sass**. Le SCSS n'est PAS compile
sur le serveur. Les fichiers compiles `public/assets/css/*.css` sont donc
**versionnes dans Git**. A chaque modification du SCSS en local :

```bash
# 1. Compiler main.scss -> public/assets/css/main.css (sass, VS Code, Prepros...)
# 2. Versionner le resultat
git add public/assets/css/
git commit -m "refonte: mise a jour CSS compile"
```

> `data/config/database.php` et `data/config/mail.php` sont aussi versionnes :
> ils ne contiennent plus aucun secret (les credentials sont dans `.env`).

## Etapes de deploiement

### 1. Preparer le fichier .env

Creer `.env` a la racine avec les vraies valeurs OVH :

```env
DB_HOST=sql.xxxx.ovh.net
DB_NAME=db_pcom
DB_USER=votre_user_bdd
DB_PASS=votre_mot_de_passe_bdd
MAIL_HOST=smtp.mail.ovh.net
MAIL_USER=contact@cfa-pcom.fr
MAIL_PASS=votre_mot_de_passe_smtp
MAIL_PORT=587
APP_ENV=prod
```

### 2. Changer le DocumentRoot sur OVH

**IMPORTANT** : Par defaut, OVH pointe vers `/www/`. Il faut changer vers `/www/public/`.

1. Espace client OVH → Web Cloud → Hebergements → Votre offre
2. Onglet `Multisite` → Cliquez sur votre domaine
3. `Modifier` → `Repertoire racine` → `public`
4. Enregistrer

### 3. Uploader les fichiers

L'offre Perso se deploie par **FTP uniquement** (pas de SSH, pas de rsync).

**Methode A - FTP (FileZilla)** : uploader tout le dossier du projet vers `/www/`
**en EXCLUANT** : `.git/`, `node_modules/`, `.env`, `.env.example`, `var/`, `*.log`

**Methode B - git archive (recommande)** :

```bash
git archive --format=zip -o site.zip HEAD
```

Uploader `site.zip` par FTP dans `/www/`, puis le dezipper (bouton "Extraire"
dans le gestionnaire de fichiers OVH, ou en ligne de commande si SSH dispo).
`git archive` n'inclut que les fichiers versionnes : `.env`, `var/tmp/` et
`public/uploads/` devront etre crees manuellement (etapes 1 et 4).

### 4. Creer les dossiers manquants + permissions

`var/` et `public/uploads/` ne sont pas versionnes (contenu dynamique) :

1. En FTP, creer `var/tmp/` et `public/uploads/` a la racine si absents
2. Permissions :

```bash
chmod 755 public/
chmod 755 public/uploads/
chmod 644 .env
chmod 750 var/tmp/
```

### 5. Activer HTTPS

1. Espace client OVH : activer certificat SSL (Let's Encrypt gratuit)
2. Decommenter dans `public/.htaccess` :
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
   Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
   ```

### 6. Base de donnees

1. Creer la BDD via phpMyAdmin OVH
2. Importer `data/sql/shema.sql`
3. Creer un user BDD dedie (pas root)
4. Mettre a jour `.env`

### 7. Migration des uploads existants

`public/uploads/` n'est pas dans Git (fichiers uploades via l'admin).
Si vous migrez un site existant :

1. Telecharger `/www/public/uploads/` en FTP **avant** le deploiement
2. Re-uploader le dossier apres l'etape 3
3. Idem pour la base de donnees (export phpMyAdmin avant, import apres)

## Checklist securite

| Element | Statut |
|---------|--------|
| Credentials dans `.env` | OK |
| CSS compiles versionnes dans Git | OK |
| Config BDD/Mail versionnee sans secret | OK |
| DocumentRoot = `public/` | A configurer OVH |
| HTTPS force | A activer OVH |
| HSTS | A decommenter |
| CSP | OK |
| X-Frame-Options | OK |
| X-Content-Type-Options | OK |
| Sessions securisees | OK |
| CSRF tous formulaires | OK |
| Rate limiting login | OK |
| Rate limiting recherche | OK |
| Rate limiting contact | OK |
| Upload securise | OK |
| Sanitisation HTML | OK |
| PDO preparees | OK |
| Logs hors web root | OK |
| Dossier Test/ bloque | OK |

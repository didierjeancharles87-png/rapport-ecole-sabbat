# Installation – Rapport École du Sabbat & Asa Fitoriana

Logiciel 100 % hors ligne pour Windows + XAMPP (PHP 8.2+, MySQL/MariaDB, Apache).
Aucune connexion Internet n'est nécessaire, ni pour l'installation, ni pour l'utilisation.

## 1. Installer XAMPP (une seule fois)
1. Installez XAMPP (version avec PHP 8.2 ou supérieur) dans `C:\xampp`.
2. Pas besoin d'ouvrir le panneau XAMPP : `START.bat` démarre Apache et MySQL.

## 2. Copier le logiciel
1. Décompressez `Rapport_Ecole_Sabbat_Mission_Offline.zip`.
2. Copiez le dossier **`rapport_ecole_sabbat`** dans `C:\xampp\htdocs\`.
   Le résultat doit être : `C:\xampp\htdocs\rapport_ecole_sabbat\index.php`.

## 3. Démarrer
Double-cliquez sur **`START.bat`** (dans le dossier `rapport_ecole_sabbat`).
Le navigateur s'ouvre sur : **http://localhost/rapport_ecole_sabbat/**

## 4. Installation automatique
Au premier lancement, la page d'installation s'affiche (sinon : `http://localhost/rapport_ecole_sabbat/database/install.php`).
1. Vérifiez que toutes les lignes de « Vérifications » sont vertes.
2. Laissez les réglages MySQL par défaut (serveur `localhost`, utilisateur `root`, mot de passe vide).
3. Cliquez sur **Installer** : la base `rapport_ecole`, les tables et le compte administrateur sont créés.

## 5. Première connexion
| Identifiant | Mot de passe initial |
|-------------|----------------------|
| `admin`     | `Admin@2026`         |

(Si vous avez saisi un autre mot de passe dans l'installateur, utilisez celui-ci.)
**Le logiciel vous demande de changer ce mot de passe dès la première connexion.**

## 6. Premiers pas
1. Menu **Classes** : créez vos classes (noms libres, effectif, responsable).
2. Menu **Utilisateurs** : créez les comptes *Responsable École du Sabbat* et *Responsable Mission*.
3. Menu **Rapport École du Sabbat** : saisissez le rapport de chaque classe (date = un samedi).
4. Le **Tableau de bord**, les **Statistiques** et les **Exportations** se calculent automatiquement.

## Arrêter
Double-cliquez sur **`STOP.bat`** (arrête Apache et MySQL ; les données sont conservées).

## Sauvegardes
Menu **Sauvegardes** : sauvegarde ZIP / SQL, téléchargement, restauration, sauvegarde automatique (par défaut tous les 7 jours, dans `storage\backups`). Copiez régulièrement un ZIP sur une clé USB.

## Problèmes fréquents
| Problème | Solution |
|----------|----------|
| « Connexion MySQL impossible » | Lancez `START.bat` et attendez ~10 s ; vérifiez qu'aucun autre MySQL n'utilise le port 3306. |
| Page blanche / 404 | Vérifiez l'emplacement : `C:\xampp\htdocs\rapport_ecole_sabbat\`. |
| « Extension zip manquante » | Dans `C:\xampp\php\php.ini`, retirez le `;` devant `extension=zip`, puis relancez START.bat. |
| Restauration d'un gros fichier refusée | Augmentez `upload_max_filesize` et `post_max_size` dans `php.ini`. |
| Mot de passe administrateur perdu | Supprimez `storage\installed.lock`, rouvrez `database/install.php` et réinstallez : l'installateur réinitialise le compte admin **sans effacer les données**. |

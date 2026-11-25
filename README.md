# Rapport École du Sabbat & Asa Fitoriana

Logiciel de gestion hors ligne (PHP 8.2+, MySQL/MariaDB, Bootstrap 5 et Chart.js **locaux**, aucun CDN) pour suivre les rapports hebdomadaires de l'École du Sabbat et les activités missionnaires (Asa Fitoriana).
Installation : voir `INSTALLATION.md`.

## Modules
- **Tableau de bord** : totaux, présence, étude quotidienne, activités, baptêmes, offrandes ; comparaison avec la période précédente ; évolution sur 12 semaines ; Top 3 des classes ; filtres semaine / mois / année / dates / classe.
- **Classes** : nom libre, code, responsable, effectif, statut.
- **Rapport École du Sabbat** : un rapport par classe et par Sabbat (unique), calculs automatiques (total présence, taux de présence, taux d'étude), rapport global de toutes les classes pour une date.
- **Asa Fitoriana / Mission** : module indépendant (rapports mission + suivi des intéressés avec statuts + statistiques missionnaires).
- **Statistiques et Top 3** : 11 classements, classement général avec score normalisé et pondérations modifiables (administrateur), évolutions hebdo/mensuelle/annuelle, régression linéaire, moyennes, analyse automatique.
- **Rapports** : sections A (École du Sabbat) et B (Mission) avec filtres de période et de classe.
- **Exportation** : PDF, Excel (XLSX), PowerPoint (PPTX) pour École du Sabbat, Mission et Statistiques, sur la période choisie. Générés par le logiciel lui-même (aucune bibliothèque à installer).
- **Sauvegardes** : ZIP / SQL, téléchargement, restauration (avec sauvegarde de sécurité), sauvegarde automatique, historique.
- **Utilisateurs et permissions** : Administrateur, Responsable École du Sabbat, Responsable Mission ; matrice de droits modifiable ; historique des actions.

## Règles de calcul
- Total présence = membres présents + visiteurs.
- Taux de présence = total présence ÷ effectif de la classe × 100.
- Taux d'étude quotidienne = membres ayant étudié ÷ effectif × 100.
- Les totaux « activités », « études bibliques », « Ala Sarona », « terminées », « baptêmes » additionnent École du Sabbat + Mission. Les offrandes proviennent de l'École du Sabbat.
- Top 3 « livres » et « tracts » : rapports Mission (le rapport École du Sabbat n'a qu'un champ « livres ou tracts », compté dans le total « Livres et tracts distribués »).
- Top 3 « étude quotidienne » et « présence » : classés par taux (équitable entre classes de tailles différentes). Les autres : classés par valeur.
- **Score global** : chaque indicateur est ramené entre 0 et 1 (valeur ÷ meilleure classe), multiplié par sa pondération, puis ramené sur 100.
- Progression = variation en % par rapport à la période précédente de même nature (semaine, mois, année ou durée équivalente).

## Sécurité
PDO + requêtes préparées, échappement XSS, jetons CSRF, sessions durcies (HttpOnly, SameSite, délai d'inactivité), `password_hash`, limitation des tentatives de connexion, contrôle des permissions côté serveur, dossiers `backend/`, `storage/` et fichiers `.sql` bloqués par `.htaccess`.

## Structure
`frontend/` (pages, composants, assets locaux) · `backend/` (config, helpers, models, services) · `database/` (schéma SQL + install.php) · `storage/` (sauvegardes, exports).
Aucun mot de passe n'est stocké en clair ; `backend/config/config.php` est créé par l'installateur.

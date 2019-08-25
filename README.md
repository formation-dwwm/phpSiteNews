# Bienvenue sur mon Site de News

Les utilisateurs sont invités à se connecter.

Les utilisateurs peuvent consultés ou créer des news mais pas les modifier. 
L'administrateur peut modifier et supprimer des news.

## 1-Installation

Langage en vanilla PHP 7.3.
Les bases de données sont fournies: mySQL 5.7
  - phpauth.sql
  - tpnews

Le site fonctionne sur WampServer avec les bases de données installés sur votre machine.

## 2-Enregistrement Utilisateur

Pour la simplification d'accès au Site de news je vous communique vos identifiants.
Un fichier JSON vous propose un login administrateur et utilisateur.

#### a.confirmation d'email de l'utilisateur par url
Work In Progress : Visualisation de l'url dans le navigateur.
L'URL type de confirmation d'email est de type :
```html
http://127.0.0.1:8080/tpnews/confirm_token.php?id=4&token=PcZKkp8GEqcbAwcZFNhxwKcgw2jjY78V6nZoMAlyzJ18QrQuNvHHCHPxmMgX
```
Après vous êtes enregistrer, vous devrez récépérer votre id utilisateur et votre token depuis la base de données pour réaliser l'url de confirmation de token avec votre base de données

#### b.confirmation de demande de changement de mot de passe par url
Work In Progress : Visualisation de l'url dans le navigateur.
L'URL type de demande de changement de mot de passe est de type :
```html
http://127.0.0.1:8080/tpnews/confirm_mdp.php?mdp=6w6loaoe
```
La génération du code est fixe pour url de confirmation de changement de mot de passe.

### c.recaptcha
Le recaptcha est visible sur le endpoint :
```html
http://127.0.0.1:8080/tpnews/login.php
```
Mais il est desactivé pour les besoins du développement en cours.



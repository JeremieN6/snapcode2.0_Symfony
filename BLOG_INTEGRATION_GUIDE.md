# Guide d'intégration du Blog SnapCode™ Agency

## 📋 Vue d'ensemble

Ce guide détaille l'intégration complète du système de blog dans votre site principal `agence.jeremiecode.fr`. Le blog est maintenant accessible via `/blog` au lieu du sous-domaine séparé, ce qui améliore considérablement le SEO.

## 🎯 Objectifs atteints

✅ **SEO optimisé** : Le blog est maintenant sur le même domaine que votre site principal  
✅ **URLs propres** : `/blog`, `/blog/article-slug`, `/blog/category/category-slug`  
✅ **Interface d'administration** : Gestion complète via EasyAdmin  
✅ **Sitemap automatique** : Génération automatique avec tous les articles  
✅ **Responsive design** : Compatible mobile et desktop  
✅ **Optimisations techniques** : Balises meta, Open Graph, structured data  

## 🏗️ Architecture

### Entités créées
- **Posts** : Articles du blog avec SEO, catégories, mots-clés
- **Categories** : Catégories hiérarchiques avec compteurs
- **Keywords** : Mots-clés pour le référencement
- **Comments** : Système de commentaires avec modération

### Contrôleurs
- **BlogController** : Affichage public du blog
- **SitemapController** : Génération du sitemap.xml
- **Admin/\*CrudController** : Interfaces d'administration

### Services
- **SitemapService** : Génération automatique du sitemap
- **SlugService** : Génération automatique des URLs SEO-friendly

## 🚀 Installation et configuration

### 1. Migration de la base de données

```bash
# Exécuter la migration
php bin/console doctrine:migrations:migrate

# Importer les données d'exemple
php bin/console app:migrate-blog-data
```

### 2. Configuration des permissions

Assurez-vous que le répertoire `public/uploads/blog/` est accessible en écriture :

```bash
mkdir -p public/uploads/blog
chmod 755 public/uploads/blog
```

### 3. Test de l'intégration

```bash
# Lancer les tests automatiques
php bin/console app:test-blog-integration
```

## 📝 Utilisation

### Interface d'administration

Accédez à `/admin` et naviguez vers la section "Blog" :

1. **Articles** : Créer, modifier, publier des articles
2. **Catégories** : Organiser les articles par thématiques
3. **Mots-clés** : Ajouter des tags pour le SEO
4. **Commentaires** : Modérer les commentaires des visiteurs

### Création d'un article

1. Aller dans **Admin > Blog > Articles**
2. Cliquer sur **"Créer Article"**
3. Remplir les champs obligatoires :
   - **Titre** : Titre de l'article
   - **Contenu** : Corps de l'article (éditeur WYSIWYG)
   - **Auteur** : Sélectionner l'auteur
   - **Catégories** : Choisir une ou plusieurs catégories
4. Optimiser le SEO :
   - **Titre SEO** : Titre optimisé (60 caractères max)
   - **Description SEO** : Description (160 caractères max)
   - **Mots-clés** : Ajouter des tags pertinents
5. Options :
   - **Publié** : Cocher pour publier immédiatement
   - **Article en vedette** : Mettre en avant sur la page d'accueil
   - **Image à la une** : Upload d'une image

### URLs disponibles

- **`/blog`** : Page d'accueil du blog
- **`/blog/{slug}`** : Page d'un article
- **`/blog/category/{slug}`** : Articles d'une catégorie
- **`/blog/search?q=terme`** : Recherche d'articles
- **`/sitemap.xml`** : Sitemap automatique

## 🔧 Fonctionnalités avancées

### Génération automatique des slugs

Les slugs sont générés automatiquement à partir des titres :
- Suppression des accents
- Conversion en minuscules
- Remplacement des espaces par des tirets
- Suppression des caractères spéciaux

### Optimisations SEO intégrées

Chaque page inclut automatiquement :
- Balises meta title et description
- Open Graph pour les réseaux sociaux
- Twitter Cards
- Structured data (JSON-LD)
- Canonical URLs

### Sitemap dynamique

Le sitemap se met à jour automatiquement et inclut :
- Pages statiques du site
- Tous les articles publiés
- Toutes les catégories
- Priorités et fréquences de mise à jour

## 📊 Migration des données existantes

### Depuis l'ancien blog

Pour migrer vos articles existants depuis `le-blog.jeremiecode.fr` :

1. **Export SQL** : Exportez les données depuis phpMyAdmin de l'ancien blog
2. **Adaptation** : Adaptez la structure aux nouvelles entités
3. **Import** : Utilisez la commande de migration ou importez manuellement

### Script de migration personnalisé

Vous pouvez modifier le fichier `src/Command/MigrateBlogDataCommand.php` pour adapter la migration à vos données spécifiques.

## 🎨 Personnalisation

### Templates

Les templates sont dans `templates/blog/` :
- **`base_blog.html.twig`** : Template de base avec SEO
- **`index.html.twig`** : Page d'accueil du blog
- **`show.html.twig`** : Page d'article individuel
- **`category.html.twig`** : Page de catégorie
- **`search.html.twig`** : Page de résultats de recherche

### Styles CSS

Les styles sont intégrés dans `base_blog.html.twig` et peuvent être personnalisés selon vos besoins.

## 🔍 SEO et référencement

### Bonnes pratiques implémentées

1. **URLs SEO-friendly** : `/blog/mon-article-seo`
2. **Balises meta optimisées** : Title, description, keywords
3. **Structured data** : Markup JSON-LD pour les moteurs de recherche
4. **Sitemap XML** : Indexation automatique
5. **Robots.txt** : Configuration optimisée
6. **Open Graph** : Partage optimisé sur les réseaux sociaux

### Vérifications recommandées

1. **Google Search Console** : Soumettre le sitemap
2. **PageSpeed Insights** : Vérifier les performances
3. **Rich Results Test** : Tester les structured data
4. **Mobile-Friendly Test** : Vérifier la compatibilité mobile

## 🚨 Dépannage

### Problèmes courants

**Erreur 404 sur `/blog`**
- Vérifier que les routes sont bien configurées
- Vider le cache : `php bin/console cache:clear`

**Images non affichées**
- Vérifier les permissions du dossier `public/uploads/blog/`
- Vérifier le chemin dans la configuration EasyAdmin

**Sitemap vide**
- Vérifier qu'il y a des articles publiés
- Tester la génération : `php bin/console app:test-blog-integration`

### Logs et debugging

```bash
# Voir les logs Symfony
tail -f var/log/dev.log

# Vider le cache
php bin/console cache:clear

# Vérifier la base de données
php bin/console doctrine:schema:validate
```

## 📈 Prochaines étapes

1. **Contenu** : Migrer vos articles existants
2. **SEO** : Soumettre le sitemap à Google Search Console
3. **Analytics** : Configurer le suivi des performances
4. **Optimisation** : Analyser et améliorer les temps de chargement
5. **Promotion** : Créer des liens internes depuis vos pages de services

## 📞 Support

Pour toute question ou problème :
1. Consulter les logs dans `var/log/`
2. Utiliser la commande de test : `php bin/console app:test-blog-integration`
3. Vérifier la documentation Symfony et EasyAdmin

---

**Félicitations ! Votre blog est maintenant intégré et optimisé pour le SEO ! 🎉**

# BlogAPIREST

---

Backend d'une solution de gestion d'articles de blogs.
Un export de notre base de données est disponible dans le dossier Resources situé à la racine du projet.
Une collection de requêtes clientes réalisées avec POSTMAN est également disponible dans ce même dossier.

## URL d'accès à notre Frontend

Nous avons un client permettant de s'identifier, de consulter les articles et d'en créer.
Il est accessible via le lien suivant : 'https://blogfi.faister.fr/index.php/v1/Api/Client/login/'

## URLs d'accès à notre backend

Notre projet étant publié en ligne, il y a deux manières d'accèder à notre backend:
    - En localhost via : 'http://localhost/nom_du_dossier/index.php/v1/Api/Auth//' (par exemple pour l'authentification)
    - Via le lien de l'hébergeur : 'https://blogfi.faister.fr/index.php/v1/Api/Auth//'

Cela permet de choisir la méthode préférée lors des tests.

- Pour l'authentification (POST) (et la génération du token) : 'https://blogfi.faister.fr/index.php/v1/Api/Auth//'

- Pour la consultation de tous les articles (GET) : 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Articles/'
  
- Pour la consultation d'un article en particulier (GET) : 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Articles/IdArticle' (IdArticle étant un entier)

- Pour la publication d'un article (POST) : 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Publish/'

- Pour la modification d'un article (PUT) (en tant que publisher sur son propre article seulement) : 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Publish/IdArticle'

- Pour la suppression d'un article (DELETE) (en tant que 'moderator' seulement): 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Publish/IdArticle'

- Pour la publication d'un vote sur un article (POST) (en tant que 'publisher' uniquement) : ''https://blogfi.faister.fr/index.php/v1/Api/Blog/Vote/IdArticle'

- Pour la modification d'un vote sur un article (PUT) : 'https://blogfi.faister.fr/index.php/v1/Api/Blog/Vote/IdArticle'

- Pour la suppression d'un vote sur un article (DELETE) : ''https://blogfi.faister.fr/index.php/v1/Api/Blog/Vote/IdArticle'

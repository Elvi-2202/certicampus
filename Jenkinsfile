pipeline {
    agent any

    stages {
        stage('Start containers') {
            steps {
                // On repart sur du propre à chaque build
                sh 'docker-compose down -v'
                sh 'docker-compose up -d --build'
                
                // On laisse MySQL respirer un peu avant les commandes suivantes
                sh 'sleep 15'
            }
        }

        stage('Composer install') {
            steps {
                // On installe les dépendances dans le container PHP
                sh 'docker-compose exec -T php composer install --no-interaction --prefer-dist'
            }
        }

        stage('Database Setup') {
            steps {
                // Création de la base si elle n'existe pas
                sh 'docker-compose exec -T php php bin/console doctrine:database:create --if-not-exists || true'
                
                // Exécution des migrations
                sh 'docker-compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction'
            }
        }

        stage('Verify App') {
            steps {
                // Une petite vérification pour s'assurer que Symfony répond
                sh 'docker-compose exec -T php php bin/console about'
            }
        }
    }

    post {
        always {
            // Nettoyage pour ne pas encombrer le serveur Jenkins
            sh 'docker-compose down'
        }
        success {
            echo "Félicitations Elvira ! Le build est passé au vert. 🚀"
        }
        failure {
            echo "Aïe, le build a échoué. Vérifie les logs de l'étape qui a bloqué."
        }
    }
}
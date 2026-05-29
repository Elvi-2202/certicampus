pipeline {
    agent any

    stages {
        stage('Start containers') {
            steps {
                // Nettoyage et lancement à la racine
                sh 'docker-compose down -v'
                sh 'docker-compose up -d --build'
                sh 'sleep 15'
            }
        }

        stage('Composer install') {
            steps {
                // Plus besoin de spécifier le working-dir, Docker utilise /var/www par défaut
                sh 'docker-compose exec -T php composer install --no-interaction --prefer-dist'
            }
        }

        stage('Verify Symfony') {
            steps {
                sh 'docker-compose exec -T php php bin/console --version'
            }
        }

        stage('Database Setup') {
            steps {
                sh 'sleep 10'
                // Création de la DB
                sh 'docker-compose exec -T php php bin/console doctrine:database:create --if-not-exists || true'
                // Migrations
                sh 'docker-compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction'
            }
        }
    }

    post {
        always {
            sh 'docker-compose down'
        }
        success {
            echo "Succès ! Ton projet est disponible sur http://localhost:8080"
        }
        failure {
            echo "Le build a échoué. Vérifie les volumes ou la configuration DB."
        }
    }
}
pipeline {
    agent any

    stages {
        stage('Start containers') {
            steps {
                // FORCE LE NETTOYAGE DES ANCIENS CONTAINERS PAR LEUR NOM
                sh 'docker rm -f symfony_db symfony_php symfony_nginx || true'
                
                // Nettoyage classique
                sh 'docker-compose down -v'
                
                // Lancement
                sh 'docker-compose up -d --build'
                sh 'sleep 15'
            }
        }
        
        // ... reste de tes étapes (Composer, Database, etc.) ...
        stage('Composer install') {
            steps {
                sh 'docker-compose exec -T php composer install --no-interaction --prefer-dist'
            }
        }

        stage('Database Setup') {
            steps {
                sh 'docker-compose exec -T php php bin/console doctrine:database:create --if-not-exists || true'
                sh 'docker-compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction'
            }
        }

        stage('Verify App') {
            steps {
                sh 'docker-compose exec -T php php bin/console about'
            }
        }
    }

    post {
        always {
            sh 'docker-compose down'
        }
    }
}
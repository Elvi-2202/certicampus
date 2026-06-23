pipeline {
    agent any

    environment {
        PHP_CONTAINER = 'symfony_php'
    }

    stages {

        stage('Checkout') {
            steps {
                echo 'Récupération du code source...'
                checkout scm
            }
        }

        stage('Build') {
            steps {
                echo 'Lancement des containers Docker...'
                sh 'docker compose up -d --build'
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installation des dépendances Composer...'
                sh 'docker compose exec -T php composer install --no-interaction --prefer-dist'
            }
        }

        stage('Cache Warmup') {
            steps {
                echo 'Réchauffement du cache Symfony...'
                sh 'docker compose exec -T php php bin/console cache:warmup --env=test'
            }
        }

        stage('Database Migration') {
            steps {
                echo 'Exécution des migrations...'
                sh 'docker compose exec -T php php bin/console doctrine:migrations:migrate --no-interaction --env=test'
            }
        }

        stage('Tests') {
            steps {
                echo 'Lancement des tests PHPUnit...'
                sh 'docker compose exec -T php php bin/phpunit --testdox'
            }
        }

    }

    post {
        success {
            echo '✅ Pipeline terminé avec succès !'
        }
        failure {
            echo '❌ Pipeline échoué — vérifiez les logs.'
        }
        always {
            echo 'Nettoyage...'
            sh 'docker compose down'
        }
    }
}
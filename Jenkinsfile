pipeline {
    agent any

    stages {
        stage('Start containers') {
            steps {
                sh 'docker rm -f symfony_db symfony_php symfony_nginx || true'
                sh 'docker-compose down -v || true'
                sh 'docker-compose up -d --build'
                sh 'sleep 15'
            }
        }

        stage('Debug') {
            steps {
                sh 'echo "=== Workspace Jenkins ===" && ls -la'
                sh 'echo "=== Dans le container /var/www ===" && docker-compose exec -T php ls -la /var/www'
            }
        }

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
            sh 'docker-compose down || true'
            cleanWs()
        }
    }
}
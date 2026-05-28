pipeline {
    agent any

    stages {
        stage('Start containers') {
            steps {
                dir('backend') {
                    // Nettoyage des anciens containers et volumes orphelins
                    sh 'docker-compose down -v'
                    // Build et lancement
                    sh 'docker-compose up -d --build'
                    
                    // On attend que les services soient prêts (MySQL notamment)
                    sh 'sleep 15'
                }
            }
        }

        stage('Composer install') {
            steps {
                dir('backend') {
                    // On force le working-dir car ton code est dans /var/www/backend
                    sh 'docker-compose exec -T php composer install --no-interaction --prefer-dist --working-dir=/var/www/backend'
                }
            }
        }

        stage('Verify Symfony') {
            steps {
                dir('backend') {
                    // Vérification que Symfony répond bien
                    sh 'docker-compose exec -T php php /var/www/backend/bin/console --version'
                }
            }
        }

        stage('Database Setup') {
            steps {
                dir('backend') {
                    // Petite pause pour être sûr que MySQL accepte les connexions
                    sh 'sleep 10'

                    // Création de la DB (le || true évite de planter si elle existe déjà)
                    sh 'docker-compose exec -T php php /var/www/backend/bin/console doctrine:database:create --if-not-exists || true'
                    
                    // Exécution des migrations
                    sh 'docker-compose exec -T php php /var/www/backend/bin/console doctrine:migrations:migrate --no-interaction'
                    
                    // Optionnel : Si tu n'as pas encore de migrations mais juste un schéma :
                    // sh 'docker-compose exec -T php php /var/www/backend/bin/console doctrine:schema:update --force'
                }
            }
        }
    }

    post {
        always {
            dir('backend') {
                // On arrête tout proprement après le build
                sh 'docker-compose down'
            }
        }
        success {
            echo "Le déploiement et les tests Docker se sont terminés avec succès !"
        }
        failure {
            echo "Le build a échoué. Vérifiez les logs de 'Composer install' ou 'Database Setup'."
        }
    }
}
pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                echo 'Récupération du code source...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installation des dépendances Composer...'
                sh '''
                    cd $WORKSPACE
                    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
                    php composer-setup.php
                    php composer.phar install --no-interaction --prefer-dist
                '''
            }
        }

        stage('Tests') {
            steps {
                echo 'Lancement des tests PHPUnit...'
                sh 'cd $WORKSPACE && php vendor/bin/phpunit --testdox'
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
    }
}
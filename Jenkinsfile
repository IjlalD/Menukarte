pipeline {
    agent any

    environment {
        TEST_DIR = "/var/www/html/menukarte.test"
        STAGING_DIR = "/var/www/html/menukarte.staging"
        GIT_REPO = "https://github.com/IjlalD/Menukarte"
        GIT_BRANCH = "main"
    }

    stages {
        stage('Checkout from GitHub') {
            steps {
                git url: "${GIT_REPO}", branch: "${GIT_BRANCH}"
            }
        }

        stage('Deploy to Test Environment') {
            steps {
                sh """
                    sudo rm -rf ${TEST_DIR}/*
                    sudo mkdir -p ${TEST_DIR}
                    sudo cp -R * ${TEST_DIR}/
                    sudo chown -R www-data:www-data ${TEST_DIR}
                """
            }
        }

        stage('Run Tests in Test Environment') {
            steps {
                dir("${TEST_DIR}") {
                    // Adjust to your test command — placeholder for PHP projects
                    sh "vendor/bin/phpunit --configuration phpunit.xml || exit 1"
                }
            }
        }

        stage('Deploy to Staging Environment') {
            when {
                expression { currentBuild.currentResult == 'SUCCESS' }
            }
            steps {
                sh """
                    sudo rm -rf ${STAGING_DIR}/*
                    sudo mkdir -p ${STAGING_DIR}
                    sudo cp -R * ${STAGING_DIR}/
                    sudo chown -R www-data:www-data ${STAGING_DIR}
                """
            }
        }
    }

    post {
        failure {
            echo "❌ Tests failed. No deployment to staging."
        }
        success {
            echo "✅ Staging deployment successful."
        }
    }
}

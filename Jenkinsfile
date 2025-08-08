pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git url: 'file:///var/www/html/menukarte', branch: 'main'
            }
        }

        stage('Deploy to Test') {
            steps {
                sh 'rm -rf /var/www/html/menukarte.test/*'
                sh 'cp -R * /var/www/html/menukarte.test/'
            }
        }

        stage('Run Unit Tests') {
            steps {
                // Change this to your actual test command
                sh 'phpunit --configuration phpunit.xml || exit 1'
            }
        }

        stage('Deploy to Staging') {
            when {
                expression { currentBuild.resultIsBetterOrEqualTo('SUCCESS') }
            }
            steps {
                sh 'rm -rf /var/www/html/menukarte.staging/*'
                sh 'cp -R * /var/www/html/menukarte.staging/'
            }
        }
    }

    post {
        failure {
            echo "❌ Tests failed. Staging not updated."
        }
        success {
            echo "✅ Deployment to staging successful."
        }
    }
}

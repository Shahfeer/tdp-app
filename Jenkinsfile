pipeline {
    agent any

    environment {
        // Define the Docker image name
        DOCKER_IMAGE = 'yeejaidocker/tdp_call_app:latest'
    }

    stages {
        stage('Checkout') {
            steps {
                // Checkout the Git repository
                git branch: 'main', 
                    changelog: false, 
                    credentialsId: 'Shahfeer-github', 
                    poll: false, 
                    url: 'https://github.com/Shahfeer/tdp_call.git'
            }
        }

        stage('Docker Build') {
            steps {
                // Build the Docker image from the Dockerfile
                sh '''
                    docker build -t tdp_call_app -f tdp_call/Dockerfile tdp_call/
                '''
            }
        }

        stage('Docker Push') {
            steps {
                // Use credentials to log in to Docker Hub
                withCredentials([usernamePassword(credentialsId: '60792a3f-4514-422c-929a-facaacdaa144', passwordVariable: 'dockerpwd', usernameVariable: 'dockerrun')]) {
                    sh '''
                        echo "$dockerpwd" | docker login -u "$dockerrun" --password-stdin
                    '''
                }

                // Tag the image and push it to Docker Hub
                sh '''
                    docker tag tdp_call_app yeejaidocker/tdp_call_app:latest
                    docker push yeejaidocker/tdp_call_app:latest
                '''
            }
        }

        stage('Deploy') {
            steps {
                // Stop and remove any existing container named "jenkins_container"
                sh '''
                    docker stop jenkins_container || true
                    docker rm jenkins_container || true
                   #Run the container on port 8085
                    docker run -d --restart always --name jenkins_container -p 8085:80 yeejaidocker/tdp_call_app:latest
                '''
            }
        }
    }

    post {
        success {
            echo "Deployment successful!"
        }
        failure {
            echo "Deployment failed!"
        }
    }
}
#####

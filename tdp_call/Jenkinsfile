pipeline {
    agent any

    stages {
        stage('checkout') {
            steps {
                git branch: 'main', changelog: false, credentialsId: 'Shahfeer-github', poll: false, url: 'https://github.com/Shahfeer/tdp_call.git'
            }
        }

        stage('docker build') {
            steps {
                sh 'docker build -t jenkins -f tdp_call/Dockerfile tdp_call/'
            }
        }

        stage('docker push') {
            steps {
                withCredentials([usernamePassword(credentialsId: '60792a3f-4514-422c-929a-facaacdaa144', passwordVariable: 'dockerpwd', usernameVariable: 'dockerrun')]) {
                    sh 'docker login -u ${dockerrun} -p ${dockerpwd}' 
                }
                sh 'docker tag jenkins yeejaidocker/jenkins:latest'
                sh 'docker push yeejaidocker/jenkins:latest'
            }
        }

        stage('deploy') {
            steps {
                sh '''
                    docker stop jenkins_container || true
                    docker rm jenkins_container || true
                    docker run -d --name jenkins_container -p 8085:8080 yeejaidocker/jenkins:latest
                '''
            }
        }
    }
}


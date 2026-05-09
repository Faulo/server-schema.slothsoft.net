pipeline {
    agent {
        label 'mörkö'
    }
    options {
        disableConcurrentBuilds()
        disableResume()
        disableRestartFromStage()
    }
    stages {
        stage('Load environment') {
            steps {
                script {
                    withEnvFile {
                        stage ('Run tests') {
                            docker.image("faulo/farah:${PHP_VERSION}").inside('-v /var/vhosts/${SERVER_NAME}:${WORKSPACE}/data') {
                                callShell 'composer install --no-interaction --optimize-autoloader --classmap-authoritative'
                                callShell 'composer exec server-clean cache logs'

                                catchError(buildResult: 'UNSTABLE', catchInterruptions: false) {
                                    callShell 'composer exec phpunit -- --log-junit report.xml'
                                }

                                junit 'report.xml'
                            }
                        }
                    }
                }
            }
        }
    }
}
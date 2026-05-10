pipeline {
    agent {
        label 'Mörkö'
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
                        docker.image("faulo/farah:${PHP_VERSION}").inside('-v ${STACK_NAME}_farah-data:${WORKSPACE}/data') {
                            stage ('Composer setup') {
                                callShell 'composer install --no-interaction --optimize-autoloader --classmap-authoritative'
                                callShell 'composer exec server-clean cache logs'
                            }
                            stage ('Run tests') {
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
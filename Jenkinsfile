pipeline {
	agent {
	    label 'jenkins && docker'
	}
    stages {
        stage('Load environment') {
            steps {
                script {
					withEnv(readFile('.env').split('\n') as List) {
						stage ('Pull image') {
							sh "docker image pull faulo/farah:8.0"
						}
						stage ('Run tests') {
							docker.image("faulo/farah:8.0").inside {
								sh 'composer update --no-interaction'
								sh 'composer exec phpunit -- --log-junit report.xml'
								junit 'report.xml'
								stash name:'lock', includes:'composer.lock'
							}
						}
						stage ('Deploy stack') {
							dir("/var/vhosts/schema") {
								checkout scm
								def service = env.STACK_NAME + '_' + env.STACK_NAME
								unstash 'lock'
								sh "docker stack deploy schema --detach=true --prune --resolve-image=never -c=docker-compose.yml"
								sh "docker service update --force " + service
								//sh 'docker exec $(docker ps -q -f name=' + service + ') composer install --no-interaction --no-dev'
							}
						}
					}
                }
            }
        }
	}
}
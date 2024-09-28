pipeline {
	agent {
		label 'jenkins && docker'
	}
	stages {
		stage('Load environment') {
			steps {
				script {
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
							unstash 'lock'
							
							sh "mkdir -p assets scripts src html data log"
							sh "chmod 777 . assets scripts src html data log"							
							
							def service = "schema_schema"
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
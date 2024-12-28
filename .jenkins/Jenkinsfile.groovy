pipeline {
	agent {
		label 'backend && mörkö'
	}
	stages {
		stage('Load environment') {
			steps {
				script {
					withEnv(readFile('.env').split('\n') as List) {
						stage('Setup dependencies') {
							callShell "docker pull faulo/farah:${PHP_VERSION}"

							env.DOCKER_OS_TYPE = callShellStdout 'docker info --format {{.OSType}}'
							env.DOCKER_WORKDIR = callShellStdout 'docker image inspect faulo/farah:${PHP_VERSION} --format={{.Config.WorkingDir}}'
						}
						stage('Build image') {
							callShell "docker compose -f .jenkins/docker-compose-build.yml build"
						}
						stage ('Run tests') {
							docker.image("tmp/${STACK_NAME}:latest").inside {
								callShell 'composer install --no-interaction'

								try {
									callShell 'composer exec phpunit -- --log-junit report.xml'
								} catch(e) {
									currentBuild.result = "UNSTABLE"
								}

								junit 'report.xml'
							}
						}
						stage ('Deploy container') {
							callShell "docker stack deploy ${STACK_NAME} --detach=false --prune --resolve-image=never -c=.jenkins/docker-compose-${DOCKER_OS_TYPE}.yml"
							callShell "docker service update --force ${STACK_NAME}_frontend"
						}
					}
				}
			}
		}
	}
}
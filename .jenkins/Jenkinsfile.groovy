pipeline {
	agent {
		label 'docker'
	}
	options {
		disableConcurrentBuilds()
		disableResume()
	}
	stages {
		stage('Load environment') {
			steps {
				script {
					withEnv(readFile('.env').split('\n') as List) {
						stage('Setup dependencies') {
							callShell "docker pull faulo/farah:${PHP_VERSION}"

							env.DOCKER_OS_TYPE = callShellStdout "docker info --format '{{.OSType}}'"
							env.DOCKER_WORKDIR = callShellStdout "docker image inspect faulo/farah:${PHP_VERSION} --format '{{.Config.WorkingDir}}'"
						}
						stage ('Run tests') {
							docker.image("faulo/farah:${PHP_VERSION}").inside {
								callShell 'composer install --no-interaction'
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
pipeline {
	agent {
		label 'mörkö'
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
							def dockerTool = tool(type: 'dockerTool', name: 'Default') + "/bin/docker"

							callShell "${dockerTool} pull faulo/farah:${PHP_VERSION}"

							env.DOCKER_OS_TYPE = callShellStdout "${dockerTool} info --format '{{.OSType}}'"
							env.DOCKER_WORKDIR = callShellStdout "${dockerTool} image inspect faulo/farah:${PHP_VERSION} --format '{{.Config.WorkingDir}}'"
						}
						stage ('Run tests') {
							withDockerContainer(image: "faulo/farah:${PHP_VERSION}", toolName: 'Default', args: '-v /var/vhosts/schema.slothsoft.net:$WORKSPACE/data') {
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
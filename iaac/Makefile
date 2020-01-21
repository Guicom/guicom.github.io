deploy: create-namespaces set-context delete-current-release install-new-release wait-pod-to-be-ready run-drupal-install
deploy_update: create-namespaces set-context recreate-current-pod sleep-10 wait-pod-to-be-ready run-drupal-update

create-namespaces:
	kubectl create namespace ${ENVIRONMENT} || echo 'Bypass error'

set-context:
	kubectl config set-context ctx_${ENVIRONMENT} --namespace=${ENVIRONMENT}  --user=${KUBERNETES_USER} --cluster=${KUBERNETES_CLUSTER}
	kubectl config use-context ctx_${ENVIRONMENT}

delete-current-release:
	helm delete --namespace=${ENVIRONMENT} drupal-${ENVIRONMENT} || echo 'Bypass error'
	kubectl delete pvc drupal-${ENVIRONMENT}-pvc --namespace=${ENVIRONMENT} || echo 'Bypass error'
	kubectl delete pv drupal-${ENVIRONMENT}-pv --namespace=${ENVIRONMENT} || echo 'Bypass error'

install-new-release:
	helm upgrade --install --wait --recreate-pods --namespace=${ENVIRONMENT} drupal-${ENVIRONMENT} ./helm/${ENVIRONMENT}

wait-pod-to-be-ready:
	pod=$$(kubectl get po --namespace=${ENVIRONMENT} -l 'app=drupal-${ENVIRONMENT},release=drupal-${ENVIRONMENT}' -o 'jsonpath={.items[0].metadata.name}'); \
	status="False"; \
	while [ ! "$${status}" = "True" ]; do echo "waiting for pod $${pod}" && sleep 1; \
	status=$$(kubectl get pods --namespace=${ENVIRONMENT} $${pod} -o 'jsonpath={..status.conditions[?(@.type=="ContainersReady")].status}'); \
	done

run-drupal-install:
	pod=$$(kubectl get po --namespace=${ENVIRONMENT} -l 'app=drupal-${ENVIRONMENT},release=drupal-${ENVIRONMENT}' -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl exec --namespace=${ENVIRONMENT} $${pod} -- bash -c "cd /var/www/html && make drupal-install && make drupal-cim"

run-drupal-update:
	pod=$$(kubectl get po --namespace=${ENVIRONMENT} -l 'app=drupal-${ENVIRONMENT},release=drupal-${ENVIRONMENT}' -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl exec --namespace=${ENVIRONMENT} $${pod} -- bash -c "cd /var/www/html && make drupal-cim && make drupal-updb"

sleep-10:
	sleep 10

recreate-current-pod:
	pod=$$(kubectl get po --namespace=${ENVIRONMENT} -l 'app=drupal-${ENVIRONMENT},release=drupal-${ENVIRONMENT}' -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl get pod $${pod} --namespace=${ENVIRONMENT}  -o yaml | kubectl replace --force -f -

deploy_elasticsearch:
	cd kubernetes/elasticsearch/${ENVIRONMENT} && kubectl replace --force --namespace=${ENVIRONMENT} -R -f .
	kubectl delete pvc drupal-${ENVIRONMENT}-pvc --namespace=${ENVIRONMENT} || echo 'Bypass error'
	kubectl delete pv drupal-${ENVIRONMENT}-pv --namespace=${ENVIRONMENT} || echo 'Bypass error'

deploy_drupal:
	cd helm
	helm install --namespace develop -f develop.yaml drupal

build-web-image:
	cd .. && docker build -t regis.myagora.fr/socomec-web/drupal:${ENVIRONMENT} -f iaac/docker/drupal/Dockerfile .

push-web-image: build-web-image
	docker login --username ${REGISTRY_USERNAME} --password ${REGISTRY_PASSWORD} ${REGISTRY_HOSTNAME}
	docker push regis.myagora.fr/socomec-web/drupal:${ENVIRONMENT}

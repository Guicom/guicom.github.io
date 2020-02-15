deploy: create-namespaces set-context delete-current-release install-new-release wait-pod-to-be-ready run-drupal-install
deploy_update: create-namespaces set-context recreate-current-pod sleep-10 wait-pod-to-be-ready run-drupal-update

create-namespaces:
	kubectl create namespace ${NAMESPACE} || echo 'Bypass error'

set-context:
	kubectl config set-context ctx_${ENVIRONMENT} --namespace=${NAMESPACE}  --user=${KUBERNETES_USER} --cluster=${KUBERNETES_CLUSTER}
	kubectl config use-context ctx_${ENVIRONMENT}

delete-current-release:
	helm delete --namespace=${NAMESPACE} drupal-${ENVIRONMENT} || echo 'Bypass error'
	kubectl delete pvc drupal-${ENVIRONMENT}-pvc --namespace=${NAMESPACE} || echo 'Bypass error'
	kubectl delete pv drupal-${ENVIRONMENT}-pv --namespace=${NAMESPACE} || echo 'Bypass error'

install-new-release:
	helm upgrade --install --wait --recreate-pods --namespace=${NAMESPACE} drupal-${ENVIRONMENT} ./helm/${ENVIRONMENT}

# Check deployment rollout status every 10 seconds (max 10 minutes) until complete.
wait-deployment-rollout:
	ATTEMPTS=0; \
	ROLLOUT_STATUS_CMD="kubectl rollout status webfactory-drupal -n ${NAMESPACE}"; \
	until $$ROLLOUT_STATUS_CMD || [ $$ATTEMPTS -eq 60 ]; \
	do \
	$$ROLLOUT_STATUS_CMD; \
	ATTEMPTS=$$((ATTEMPTS + 1)); \
	sleep 10; \
	done


wait-pod-to-be-ready:
	pod=$$(kubectl get po --namespace=${NAMESPACE} -l "app=webfactory-drupal,release=${NAMESPACE}-drupal" -o 'jsonpath={.items[0].metadata.name}'); \
	status="False"; \
	while [ ! "$${status}" = "True" ]; do echo "waiting for pod $${pod}" && sleep 1; \
	status=$$(kubectl get pods --namespace=${NAMESPACE} $${pod} -o 'jsonpath={..status.conditions[?(@.type=="ContainersReady")].status}'); \
	done

run-drupal-install:
	pod=$$(kubectl get po --namespace=${NAMESPACE} -l "app=webfactory-drupal,release=${NAMESPACE}-drupal" -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl exec --namespace=${NAMESPACE} $${pod} -- bash -c "cd /var/www/html && make drupal-install && make drupal-cim"

run-drupal-update:
	pod=$$(kubectl get po --namespace=${NAMESPACE} -l "app=webfactory-drupal,release=${NAMESPACE}-drupal" -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl exec --namespace=${NAMESPACE} $${pod} -- bash -c "cd /var/www/html && make drupal-update"

sleep-10:
	sleep 10

recreate-current-pod:
	pod=$$(kubectl get po --namespace=${NAMESPACE} -l "app=webfactory-drupal,release=${NAMESPACE}-drupal" -o 'jsonpath={.items[0].metadata.name}'); \
	kubectl get pod $${pod} --namespace=${NAMESPACE}  -o yaml | kubectl replace --force -f -

deploy_elasticsearch:
	cd kubernetes/elasticsearch/${ENVIRONMENT} && kubectl replace --force --namespace=${NAMESPACE} -R -f .
	kubectl delete pvc drupal-${ENVIRONMENT}-pvc --namespace=${NAMESPACE} || echo 'Bypass error'
	kubectl delete pv drupal-${ENVIRONMENT}-pv --namespace=${NAMESPACE} || echo 'Bypass error'

deploy_drupal:
	cd helm
	helm install --namespace develop -f develop.yaml drupal

build-web-image:
	cd .. && docker build -t regis.myagora.fr/socomec-web/drupal:${ENVIRONMENT} -f iaac/docker/drupal/Dockerfile .

push-web-image: build-web-image
	docker login --username ${REGISTRY_USERNAME} --password ${REGISTRY_PASSWORD} ${REGISTRY_HOSTNAME}
	docker push regis.myagora.fr/socomec-web/drupal:${ENVIRONMENT}

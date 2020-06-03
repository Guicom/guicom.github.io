{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "drupal.name" -}}
{{- default .Chart.Name .Values.drupal.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
*/}}
{{- define "drupal.fullname" -}}
{{- $name := default .Chart.Name .Values.drupal.nameOverride -}}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{- define "drupal.image" -}}
{{- printf "%s/%s:%s" .Values.registry.location .Values.registry.images.drupal .Values.environment -}}
{{- end -}}

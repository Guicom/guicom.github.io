{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "content-hub.name" -}}
{{- default .Chart.Name .Values.content-hub.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
*/}}
{{- define "content-hub.fullname" -}}
{{- $name := default .Chart.Name .Values.content-hub.nameOverride -}}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" -}}
{{- end -}}


{{- define "content-hub.image" -}}
{{- printf "%s/%s:%s" .Values.registry.location .Values.registry.images.content-hub .Values.environment -}}
{{- end -}}

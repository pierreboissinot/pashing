[![Build Status](https://travis-ci.org/pierreboissinot/pashing.svg?branch=master)](https://travis-ci.org/pierreboissinot/pashing)
<img src="https://sonarcloud.io/api/project_badges/measure?project=pashing&metric=alert_status">

# Pashing

Simple realtime dashboard.

## Features
* Prometheus exporter, so you can view metrics with Grafana.

## Connected Services via api
- Wrike
- Gitlab

## About

Inspired by [Dashing](https://github.com/Shopify/dashing), a great tool to create dashboards.

This project goal is to offer the same features as Dashing but in a different technic stack:
* Sinatra => Symfony
* Batman.js => VueJs

As Dashing, we use [EventSource](https://developer.mozilla.org/en-US/docs/Web/API/EventSource) and [SSE](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events) to manage realtime data.

## Project structure

* `src/Controller`: one Controller per dashboard.
* `src/EventHandler`: one EventHandler per indicator.
* `asses/js/components`: reusable widget components.

## Todo

* refactor code to be more like a plug and play indicator.
* manage GridLayout
* measure browsers compatibility

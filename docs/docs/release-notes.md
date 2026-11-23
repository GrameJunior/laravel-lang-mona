---
title: Release notes
description: Understand the Mona 20.x release line and its compatibility with Laravel.
---

## 20.x

Mona brings the Laravel-Lang packages into one repository and one release line. Packages published from Mona share the same release version. For packages your application requires directly, use the `^20.0` Composer constraint.

This release reorganizes configuration, uses manifests to discover translation packs, and publishes files under canonical locale codes. It also changes APIs for names, routes, and models. Follow the [upgrade guide](./upgrade.md) for the changes that apply to your application.

## Versioning and compatibility

Laravel-Lang follows [Semantic Versioning](https://semver.org/). Breaking changes and dropped platform support belong in major releases. See [installation requirements](./start.md#requirements) for PHP and Laravel compatibility.

Release tags and patch notes are available in [Mona releases](https://github.com/Laravel-Lang/mona/releases). See the [Laravel support policy](https://laravel.com/docs/releases#support-policy) for Laravel's maintenance dates.

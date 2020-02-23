@extends('base')

@section('body')
    <div class="content-overlay"></div>

    {% include 'home/components/header.html.twig' %}
    {% include 'home/components/profile-menu.html.twig' %}
    {% include 'home/components/container.twig' %}
    {% include 'home/components/mobile-view.html.twig' %}
    {% include 'home/components/handlebars.html.twig' %}
    {% include 'modals/create.html.twig' %}
@endsection

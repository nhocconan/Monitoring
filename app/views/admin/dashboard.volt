{% extends 'admin/base.volt' %}
{% block title %}Dashboard{% endblock %}
{% block content %}
    <h1>Welcome, {{ user.name }}</h1>
    <p>You are logged in as an admin.</p>
    <p>You last logged in on {{ user.last_login }} from {{ user.last_login_ip }}</p>
{% endblock %}
{% extends 'clients/base.volt' %}
{% block title %}Dashboard{% endblock %}
{% block content %}
    <h1>Welcome, {{ user.name }}</h1>
    <p>Welcome to the new client dashboard. From here, you can see the monitoring status of your servers. If you have any suggestions for this dashboard, please contact us.</p>
    <p>You last logged in on {{ user.last_login }} from {{ user.last_login_ip }}</p>
{% endblock %}
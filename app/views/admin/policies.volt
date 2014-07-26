{% extends 'admin/base.volt' %}
{% block title %}Policies{% endblock %}
{% block content %}
    <table class="table">
        <thead>
        <th>ID</th>
        <th>Owner</th>
        <th>Name</th>
        <th>Logic</th>
        <th>&nbsp;</th>
        </thead>
        <tbody>
        {% for policy in policies %}
            <tr>
                <td>{{ policy.id }}</td>
                <td>{{ policy.user.name }}</td>
                <td>{{ policy.name }}</td>
                <td>{{ policy.wordy }}</td>
                <td><a class="btn btn-danger" href="/admin/deletePolicy?pid={{ policy.id }}">Delete</a></td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endblock %}
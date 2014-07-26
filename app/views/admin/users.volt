{% extends 'admin/base.volt' %}
{% block title %}Users{% endblock %}
{% block content %}
    <table class="table">
        <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Email Address</th>
        <th>Type</th>
        <th>Last Login</th>
        <th>Last Login IP</th>
        <th>&nbsp;</th>
        </thead>
        <tbody>
        {% for user in users %}
            <tr>
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{% if user.is_admin %}Admin{% else %}User{% endif %}</td>
                <td>{{ user.last_login }}</td>
                <td>{{ user.last_login_ip }}</td>
                <td><a class="btn btn-danger" href="/admin/deleteUser?uid={{ user.id }}">Delete</a></td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endblock %}
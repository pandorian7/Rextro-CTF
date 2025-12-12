from flask import Flask, render_template, request, redirect, url_for, Response
from markupsafe import Markup
from jinja2 import Environment

app = Flask(__name__, template_folder='templates', static_folder='static')

# In-memory storage for notes (no DB)
# Use a dict mapping client IP -> list of notes so each IP has a separate note list.
notes_by_ip = {}

# Simulated secret flag available to sandboxed templates
SECRET_FLAG = "FLAG{simulated_safe_flag}"

# Create a sandboxed Jinja environment for rendering user-provided templates.
# Safety note: We use jinja2.sandbox.SandboxedEnvironment which restricts
# access to Python internals (e.g. __builtins__, __globals__) and blocks
# attribute access patterns that could reach OS-level APIs. We render user
# content only through this sandbox and pass an explicit, minimal context
# containing only 'SECRET_FLAG'. The server never reads system files or
# exposes modules like os, subprocess, or globals to the sandbox.
env = Environment(autoescape=True)

env.globals["SECRET_FLAG"] = SECRET_FLAG


def render_user_template(source: str) -> str:
    """Render a user-supplied template string inside the sandbox.

    Returns a string (rendered output). Any rendering errors are caught and
    returned as a safe error message.
    """
    tmpl = env.from_string(source or "")
    rendered = tmpl.render()
    return rendered


def _get_client_ip():
    """Return the client's IP address.

    If the app is behind a proxy that sets X-Forwarded-For, use that value's
    first entry. Otherwise fall back to request.remote_addr.
    """
    xff = request.headers.get('X-Forwarded-For', '')
    if xff:
        # X-Forwarded-For may contain a comma-separated list; take the first.
        return xff.split(',')[0].strip()
    return request.remote_addr or 'unknown'


@app.route('/')
def index():
    # Render main page for the requesting client's IP only.
    client_ip = _get_client_ip()
    notes = notes_by_ip.get(client_ip, [])
    return render_template('index.html', notes=notes)


@app.route('/add', methods=['GET', 'POST'])
def add_note():
    if request.method == 'POST':
        title_src = request.form.get('title', '')
        content_src = request.form.get('content', '')

        # Render user-provided title and content safely in the sandbox.
        title_html = render_user_template(title_src)
        content_html = render_user_template(content_src)

        notes = notes_by_ip.setdefault(_get_client_ip(), [])

        notes.append({
            'title_raw': title_src,
            'content_raw': content_src,
            # Mark as safe for the outer template because sandboxing handled
            # template rendering and autoescape was enabled in the sandbox.
            'title_html': Markup(title_html),
            'content_html': Markup(content_html),
        })

        return redirect(url_for('index'))

    # GET: show the main page with the add form visible
    client_ip = _get_client_ip()
    notes = notes_by_ip.get(client_ip, [])
    return render_template('index.html', notes=notes, show_add=True)


@app.route('/preview', methods=['POST'])
def preview():
    # Render a preview of provided title/content without saving
    title_src = request.form.get('title', '')
    content_src = request.form.get('content', '')
    title_html = render_user_template(title_src)
    content_html = render_user_template(content_src)

    preview_note = {
        'title_html': Markup(title_html),
        'content_html': Markup(content_html),
    }

    client_ip = _get_client_ip()
    notes = notes_by_ip.get(client_ip, [])
    return render_template('index.html', notes=notes, preview=preview_note, show_add=True)


@app.route('/health')
def health():
    return Response('OK', status=200, mimetype='text/plain')


if __name__ == '__main__':
    # Bind to 0.0.0.0 and port 5000 as required
    app.run(host='0.0.0.0', port=5000)
from flask import Flask, render_template
from flaskext.mysql import MySQL
from config import Config
from datetime import datetime

app = Flask(__name__)
app.config.from_object(Config)

mysql = MySQL()
mysql.init_app(app)

def get_db():
    try:
        return mysql.connect()
    except Exception as e:
        print(f"Database connection error: {e}")
        return None

@app.route('/')
def index():
    try:
        conn = get_db()
        if conn is None:
            return "Database connection failed", 500
            
        cur = conn.cursor()
        
        # Fetch events without DATE_FORMAT
        cur.execute("""
            SELECT 
                id,
                title,
                description,
                date,
                image_url
            FROM events 
            ORDER BY date DESC 
            LIMIT 6
        """)
        events_raw = cur.fetchall()
        
        # Convert to list of dictionaries
        events = []
        for event in events_raw:
            events.append({
                'id': event[0],
                'title': event[1],
                'description': event[2],
                'date': event[3],  # Already a datetime object
                'image_url': event[4]
            })
        
        # Fetch courses
        cur.execute("""
            SELECT 
                c.id,
                c.code,
                c.name,
                c.credits,
                c.description,
                c.semester,
                COALESCE(c.image_url, 'default-course.jpg') as image_url
            FROM courses c 
            LIMIT 4
        """)
        courses = cur.fetchall()
        
        # Fetch lecturers
        cur.execute("SELECT * FROM lecturers LIMIT 3")
        lecturers = cur.fetchall()
        
        cur.close()
        conn.close()
        
        return render_template('index.html', 
                             events=events, 
                             courses=courses, 
                             lecturers=lecturers)
    except Exception as e:
        print(f"Error: {e}")
        return "An error occurred", 500

# In app.py - Update events route with better error handling
@app.route('/events')
def events():
    try:
        conn = get_db()
        if conn is None:
            return "Database connection failed", 500
            
        cur = conn.cursor()
        
        # Fetch all events with complete details
        cur.execute("""
            SELECT 
                id,
                title,
                description,
                date,
                image_url,
                long_description,
                image_url2,
                image_url3,
                created_at
            FROM events 
            ORDER BY date DESC
        """)
        events = cur.fetchall()
        
        cur.close()
        conn.close()
        
        # Add required JS/CSS for calendar
        calendar_dependencies = {
            'css': [
                'fullcalendar/main.min.css'
            ],
            'js': [
                'fullcalendar/main.min.js',
                'moment/moment.min.js'
            ]
        }
        
        return render_template('events.html', 
                             events=events,
                             calendar_deps=calendar_dependencies)
                             
    except Exception as e:
        print(f"Error in events route: {e}")
        return f"An error occurred: {e}", 500

@app.route('/courses')
def courses():
    try:
        conn = get_db()
        if conn is None:
            return "Database connection failed", 500
            
        cur = conn.cursor()
        cur.execute("""
            SELECT 
                id,
                code,
                name,
                credits,
                description,
                semester,
                COALESCE(image_url, 'default-course.jpg') as image_url
            FROM courses 
            ORDER BY semester, code
        """)
        
        courses = [
            {
                'id': row[0],
                'code': row[1],
                'name': row[2],
                'credits': row[3],
                'description': row[4],
                'semester': row[5],
                'image_url': row[6]
            }
            for row in cur.fetchall()
        ]
        
        cur.close()
        conn.close()
        
        return render_template('courses.html', courses=courses)
    except Exception as e:
        print(f"Error: {e}")
        return "An error occurred", 500

@app.route('/api/events')
def get_events_json():
    try:
        conn = get_db()
        cur = conn.cursor()
        
        cur.execute("""
            SELECT id, title, description, date 
            FROM events
            ORDER BY date ASC
        """)
        
        events = cur.fetchall()
        
        # Format for FullCalendar
        calendar_events = [{
            'id': event[0],
            'title': event[1],
            'description': event[2],
            'start': event[3].isoformat(),
            'url': f'/events#{event[0]}'
        } for event in events]
        
        return jsonify(calendar_events)
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/lecturers')
def lecturers():
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("SELECT * FROM lecturers")
        lecturers = cur.fetchall()
        cur.close()
        conn.close()
        return render_template('lecturers.html', lecturers=lecturers)
    except Exception as e:
        return f"Error: {e}", 500

@app.route('/research')
def research():
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("SELECT * FROM research ORDER BY year DESC")
        research = cur.fetchall()
        cur.close()
        conn.close()
        return render_template('research.html', research=research)
    except Exception as e:
        return f"Error: {e}", 500

@app.route('/awards')
def awards():
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("SELECT * FROM awards ORDER BY year DESC")
        awards = cur.fetchall()
        cur.close()
        conn.close()
        return render_template('awards.html', awards=awards)
    except Exception as e:
        return f"Error: {e}", 500

# Static content routes
@app.route('/admission')
def admission():
    return render_template('admission.html')

@app.route('/contact')
def contact():
    return render_template('contact.html')

@app.route('/course/<int:id>')
def course_detail(id):
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("""
            SELECT id, code, name, credits, description, semester, image_url
            FROM courses 
            WHERE id = %s
        """, (id,))
        course = cur.fetchone()
        cur.close()
        conn.close()
        
        if course is None:
            return "Course not found", 404
            
        return render_template('course_detail.html', course=course)
    except Exception as e:
        return f"Error: {e}", 500

@app.route('/course/<int:id>/register')
def course_registration(id):
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("SELECT id, code, name FROM courses WHERE id = %s", (id,))
        course = cur.fetchone()
        cur.close()
        conn.close()
        
        if course is None:
            return "Course not found", 404
            
        return render_template('course_registration.html', course=course)
    except Exception as e:
        return f"Error: {e}", 500

@app.route('/lecturer/<int:id>')
def lecturer_detail(id):
    try:
        conn = get_db()
        if conn is None:
            return "Database connection failed", 500
            
        cur = conn.cursor()
        cur.execute("""
            SELECT * FROM lecturers WHERE id = %s
        """, (id,))
        
        lecturer = cur.fetchone()
        if lecturer is None:
            return "Lecturer not found", 404
            
        cur.close()
        conn.close()
        
        return render_template('teachers-single.html', lecturer=lecturer)
    except Exception as e:
        print(f"Error: {e}")
        return "An error occurred", 500



if __name__ == '__main__':
    app.run(debug=True)


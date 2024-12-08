from flask import Flask, render_template
from flaskext.mysql import MySQL
from config import Config
from datetime import datetime

#sdajdjsafojasofosajfsafsddsgdoihgdoi
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
        
        # Fetch recent events with DATE_FORMAT
        cur.execute("""
            SELECT 
                id,
                title,
                description,
                DATE_FORMAT(date, '%Y-%m-%d') as formatted_date,
                image_url
            FROM events 
            ORDER BY date DESC 
            LIMIT 6
        """)
        events_raw = cur.fetchall()
        
        # Convert string dates to datetime objects
        events = []
        for event in events_raw:
            event_list = list(event)
            event_list[3] = datetime.strptime(event[3], '%Y-%m-%d')
            events.append(tuple(event_list))
        
        # Modified courses query with specific fields
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

# Dynamic content routes
@app.route('/events')
def events():
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("""
            SELECT 
                id, 
                title, 
                description, 
                DATE_FORMAT(date, '%Y-%m-%d') as formatted_date,
                image_url,
                long_description,
                image_url2,
                image_url3
            FROM events 
            ORDER BY date DESC
        """)
        events_raw = cur.fetchall()
        
        # Convert dates
        events = []
        for event in events_raw:
            event_list = list(event)
            event_list[3] = datetime.strptime(event[3], '%Y-%m-%d')
            events.append(tuple(event_list))
            
        cur.close()
        conn.close()
        return render_template('events.html', events=events)
    except Exception as e:
        return f"Error: {e}", 500

@app.route('/courses')
def courses():
    try:
        conn = get_db()
        cur = conn.cursor()
        cur.execute("SELECT * FROM courses ORDER BY semester")
        courses = cur.fetchall()
        cur.close()
        conn.close()
        return render_template('courses.html', courses=courses)
    except Exception as e:
        return f"Error: {e}", 500

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


FROM python:3.11-slim
WORKDIR /app

# Copy app sources
COPY app/ ./app/
COPY app/requirements.txt ./requirements.txt
COPY flag.txt ./flag.txt

RUN pip install --no-cache-dir -r requirements.txt

EXPOSE 5000

ENV FLASK_ENV=production

CMD ["python", "app/app.py"]

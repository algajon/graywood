FROM python:3.11-slim

ENV DEBIAN_FRONTEND=noninteractive

# Install system deps and Chrome
RUN apt-get update && apt-get install -y --no-install-recommends \
    wget ca-certificates gnupg2 unzip curl fonts-liberation libnss3 libgconf-2-4 \
    && rm -rf /var/lib/apt/lists/*

# Add Google's apt key and repo, install chrome-stable
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" \
       > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update && apt-get install -y --no-install-recommends google-chrome-stable \
    && rm -rf /var/lib/apt/lists/*

# Create app dir
WORKDIR /app

# Copy project files
COPY . /app

# Install Python deps
RUN pip install --no-cache-dir -r requirements.txt

# Non-root user
RUN groupadd -r app && useradd -r -g app app \
    && chown -R app:app /app
USER app

# Render sets $PORT; default to 10000 if not set
ENV PORT=10000

# Expose port (informational)
EXPOSE ${PORT}

# Start the FastAPI app using uvicorn
CMD ["sh", "-c", "uvicorn main:app --host 0.0.0.0 --port ${PORT} --workers 1"]
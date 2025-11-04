FROM python:3.11-slim

ENV DEBIAN_FRONTEND=noninteractive

# Install base system dependencies
RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends \
        wget ca-certificates gnupg2 unzip curl fonts-liberation libnss3 \
    && rm -rf /var/lib/apt/lists/*

# Add Google's signing key (modern way) and install Chrome
RUN mkdir -p /usr/share/keyrings \
    && wget -q -O /usr/share/keyrings/google-chrome.gpg https://dl.google.com/linux/linux_signing_key.pub \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/google-chrome.gpg] http://dl.google.com/linux/chrome/deb/ stable main" \
        > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends google-chrome-stable \
    && rm -rf /var/lib/apt/lists/*

# Set work directory
WORKDIR /app

# Copy project files
COPY . /app

# Install Python dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Create non-root user
RUN groupadd -r app && useradd -r -g app app \
    && chown -R app:app /app
USER app

# Set default port for Render (Render sets $PORT automatically)
ENV PORT=10000
EXPOSE ${PORT}

# Run the FastAPI app
CMD ["sh", "-c", "uvicorn main:app --host 0.0.0.0 --port ${PORT} --workers 1"]

FROM python:3.11-slim

ENV DEBIAN_FRONTEND=noninteractive

# Install Chromium + driver and basic dependencies
RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends \
        chromium \
        chromium-driver \
        wget \
        ca-certificates \
        curl \
        fonts-liberation \
        libnss3 \
        libxkbcommon0 \
        libasound2 \
        libxss1 \
        libgbm1 \
    && rm -rf /var/lib/apt/lists/*

# Workdir
WORKDIR /app

# Copy project files
COPY . /app

# Install Python deps
RUN pip install --no-cache-dir -r requirements.txt

# Non-root user
RUN groupadd -r app && useradd -r -g app app \
    && chown -R app:app /app
USER app

ENV PORT=10000
EXPOSE ${PORT}

CMD ["sh", "-c", "uvicorn main:app --host 0.0.0.0 --port ${PORT} --workers 1"]

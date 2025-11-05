FROM python:3.11-slim

ENV DEBIAN_FRONTEND=noninteractive

# Install Chromium, ChromeDriver, and required shared libs
RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends \
        chromium \
        chromium-driver \
        wget \
        ca-certificates \
        curl \
        fonts-liberation \
        libnss3 \
        libx11-6 \
        libx11-xcb1 \
        libxcb1 \
        libxcomposite1 \
        libxcursor1 \
        libxdamage1 \
        libxi6 \
        libxtst6 \
        libatk1.0-0 \
        libcups2 \
        libdrm2 \
        libxrandr2 \
        libgbm1 \
        libasound2 \
        libxss1 \
        libatk-bridge2.0-0 \
        libgtk-3-0 \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . /app

RUN pip install --no-cache-dir -r requirements.txt

RUN groupadd -r app && useradd -r -g app app \
    && chown -R app:app /app
USER app

ENV PORT=10000
EXPOSE ${PORT}

CMD ["sh", "-c", "uvicorn main:app --host 0.0.0.0 --port ${PORT} --workers 1"]

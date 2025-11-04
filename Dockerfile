FROM python:3.11-slim

ENV DEBIAN_FRONTEND=noninteractive

# First update, allowing release info change
RUN apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends \
         wget ca-certificates gnupg2 unzip curl fonts-liberation libnss3 libgconf-2-4 \
    && rm -rf /var/lib/apt/lists/*

# Add Google's apt key and repo
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" \
         > /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update --allow-releaseinfo-change \
    && apt-get install -y --no-install-recommends google-chrome-stable \
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

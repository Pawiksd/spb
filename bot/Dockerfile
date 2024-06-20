FROM node:16

# Set working directory
WORKDIR /usr/src/app

# Install dependencies
COPY package*.json ./
RUN npm install

# Copy existing application directory contents
COPY . .

# Expose port (if needed)
EXPOSE 3000

# Start the bot
CMD ["node", "bot.js"]

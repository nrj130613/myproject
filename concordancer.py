
from distutils import text_file

import nltk

class Concordancer:
    left_context_length = 50
    right_context_length = 50

    def __init__(self):
        self.sentencelist = list()
        


    def read_tokens(self, file_name):
      for line in open(file_name):
        tokens = nltk.wordpunct_tokenize(line)
        self.sentencelist.append(tokens)


        

    def find_concordance(self, query, num_words):
      self.query = query
      self.num_words = num_words
      left_context_length = 50
      right_context_length = 50


      for i in range(len(self.sentencelist)):
        if query in self.sentencelist[i]:
          for a in range(len(self.sentencelist[i])):
            if self.sentencelist[i][a] == query:
              if a >= num_words:
                resultlist_left = self.sentencelist[i][a-num_words:a:1]
              else:
                resultlist_left = self.sentencelist[i][:a:1]
                  
              if -a <= num_words:
                resultlist_right = self.sentencelist[i][a+1:a+num_words+1:1]
              else:
                resultlist_right = self.sentencelist[i][a+1::a]

              left = ' '.join(resultlist_left)
              right = ' '.join(resultlist_right)
              
              if len(left) > 50:
                left = left[-50:]
              if len(right) > 50:
                right = right[:50]

              print("{:>50} {} {:<50}".format(left, self.sentencelist[i][a], right))
        
      checking = ''

      for i in range(len(self.sentencelist)):
          if query in self.sentencelist[i]:
            checking = 'yes'
      if checking != 'yes':
      
          print("Query not found…")
            

    def compute_bigram_stats(self, query, output_file_name):
      self.output_file_name = output_file_name
      from collections import Counter
      import re
      self.query = query
      wordlist = []

      for i in range(len(self.sentencelist)):
        if query in self.sentencelist[i]:
          for a in range(len(self.sentencelist[i])):
            if self.sentencelist[i][a] == query:
              bigram = self.sentencelist[i][a] + ' ' + self.sentencelist[i][a+1]
              pattern = r'[A-Za-z]'
              match_result = re.match(pattern, self.sentencelist[i][a+1])
              if match_result is not None:
                wordlist.append(bigram)
      bigram_count = Counter(wordlist)
      sorted_count = bigram_count.most_common()
      strlist = []
      for el in sorted_count:
        word, num = el
        string = word + ' ' + str(num)
        strlist.append(string)
      
      info = '\n'.join(strlist)

      if wordlist == []:
        with open(output_file_name, mode= 'w') as f:
          f.close()
      else:
        with open(output_file_name, mode= 'w') as f:
          f.write(info)
          f.close()
    
    def find_concordance_ngram(self, query, num_words):
      self.query = query
      self.num_words = num_words
      left_context_length = 50
      right_context_length = 50
      
      query_list = query.split(' ')
      n = len(query_list)

      output = []
      for i in range(len(self.sentencelist)):  
        sentence = ' '.join(self.sentencelist[i])
        for x in range(len(self.sentencelist[i])):
          if x+n-1<len(self.sentencelist[i]):
            if self.sentencelist[i][x] == query_list[0] and self.sentencelist[i][x+1] == query_list[1] and self.sentencelist[i][x+n-1] == query_list[n-1]:
              y = x + n - 1

              if x >= num_words:
                resultlist_left = self.sentencelist[i][x-num_words:x:1]
              else:
                resultlist_left = self.sentencelist[i][:x:1]
                          
              if len(self.sentencelist[i][y:]) >= num_words:
                resultlist_right = self.sentencelist[i][y+1:y+num_words+1:1]
              else:
                resultlist_right = self.sentencelist[i][y+1::1]

              left = ' '.join(resultlist_left)
              right = ' '.join(resultlist_right)

                      
              if len(left) > 50:
                left = left[-50:]
              if len(right) > 50:
                right = right[:50]
              
              print("{:>50} {} {:<50}".format(left, query, right))

      
      checking = ''

      for i in range(len(self.sentencelist)):
        sentence = ' '.join(self.sentencelist[i])
        if query in sentence:
          checking = 'yes'
      if checking != 'yes':
        print("Query not found…")



        

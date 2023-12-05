import spacy
import json, re, requests, datetime
from gensim.models import LdaModel
from gensim.corpora.dictionary import Dictionary
from gensim import corpora

class NewsAnalyzer:

    # constructor
    def __init__(self, api_key:str):
      # ASSIGN SPACY AND API KEY
      self.nlp = spacy.load("en_core_web_sm")
      self.api_key = '1da29a51dc1d4c2a8489332fe9f0c03b'
      
    def get_data_from_api(self, query_term:str, start_date='2022-11-01') -> list:
      self.query_term = query_term
      #self.start_date = start_date
      article_list = []
      endpoint = 'https://newsapi.org/v2/everything'

      year, month, day = start_date.split('-')
      start_date = datetime.date(int(year), int(month), int(day))
      for i in range(7):
        next_day = start_date + datetime.timedelta(i)
        date = '{}-{}-{}'.format(next_day.year, next_day.month, next_day.day)

        url = f"https://newsapi.org/v2/everything?q="+query_term+"&from="+date+"&to="+date+"&apiKey=1da29a51dc1d4c2a8489332fe9f0c03b&language=en&pageSize=20"

        response = requests.get(url)
        data = json.loads(response.text)

        for key in data['articles']:
            
          article_list.append(key)
            
      return article_list


    def save_news_data_to_file(self, output_file_name:str, query_term:str, start_date='2022-11-01'):
      self.output_file_name = output_file_name
      self.query = query_term
      self.start_date = start_date
      article_list = self.get_data_from_api(query_term, start_date)

      out_file = open(output_file_name, "w") 
    
      json.dump(article_list, out_file) 

      out_file.close()
    
    def extract_clean_news_content(self, news_list:list) -> list:
      self.news_list = news_list

      import re
      import spacy
      
      raw_text_list = []
      cleaned_list = []
      for news in self.news_list:
        for key in news:
          if key == 'content':
            if news[key] is not None:
              pattern = r"[…]\s\[\+\d+\schars\]"
              find_result = re.findall(pattern, news[key])
              if find_result is not None:
                only_text = re.sub(pattern, '', news[key])

              if '\r\n' in only_text:
                text_wo_rn = only_text.replace('\r\n', ' ')
              else:
                text_wo_rn = only_text
              
              if '\n' in text_wo_rn:
                text_wo_n = text_wo_rn.replace('\n', ' ')

              else:
                text_wo_n = text_wo_rn

              html_pattern = r"\<\/?[A-Za-z]+\>"
              find_result = re.findall(html_pattern, text_wo_n)
              if find_result is not None:
                raw_text = re.sub(html_pattern, '', text_wo_n)
              else:
                raw_text = text_wo_n
              raw_text_list.append(raw_text)
            elif news[key] is None:
              pass
            
      
      for i in range(len(raw_text_list)):
        token_list = []
        nlp = spacy.load("en_core_web_sm")
        doc = nlp(raw_text_list[i])
        for token in doc:    
          if token.text.isalpha():
            token_list.append(token.text)
        cleaned_list.append(token_list)
      return cleaned_list

    def analyze_topics(self, news_data_file, num_topics) -> list:
      self.news_data_file = news_data_file
      self.num_topics = int(num_topics)

      with open(news_data_file) as f:
        data = json.load(f)

        cleaned_list = self.extract_clean_news_content(data)

      lda_dict = corpora.Dictionary(cleaned_list)
      data_set = []
      for doc in cleaned_list:
        data_set.append(lda_dict.doc2bow(doc))
      model = LdaModel(corpus=data_set, num_topics=10, id2word=lda_dict, 
                          alpha='auto', eval_every=10, random_state=10)
          
      topic_list = model.show_topics(num_topics=num_topics, num_words=10, formatted=False)

      keyword_list = []
      for topic in topic_list:
        keywords = []
        for tuples in topic[1]:
          keyword = tuples[0]
          keywords.append(keyword)
        keyword_list.append(keywords)
      return keyword_list
